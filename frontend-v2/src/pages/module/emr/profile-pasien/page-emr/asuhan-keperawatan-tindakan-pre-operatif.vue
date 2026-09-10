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
    <VPlaceload height="37rem" width="100%" class="mx-2" v-if="loadData" />
    <VCard v-else>
      <h1 class="title-akt">A. PENGKAJIAN SEBELUM OPERASI (Diisi oleh perawat ruangan)</h1>
      <div class="column" v-for="(datas) in pengkajianSebelum">
        <span class="label-akt">{{ datas.title }}</span>
        <div class="columns is-multiline pl-3 pr-3 pt-3">
          <div class="column" v-for="(data) in datas.detail" v-if="datas.type == 'textBox'"
            :class="datas.title == '1. Tanda-tanda vital' ? 'is-2' : 'is-3'">
            <span class="label-akt">{{ data.label }}</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input[data.model]" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3" v-for="(data) in datas.detail" v-else>
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                  color="primary" squere />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4 pt-0 pb-0" v-if="datas.optional">
          <VField>
            <VControl>
              <VInput type="text" v-model="input[datas.optional.model]" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column">
        <span class="label-akt">7. Hasil Px Radiologi</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.hasilRadiologi_1" true-value="thorax" label="Thorax" class="p-0" color="primary"
                  squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketThorax" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.hasilRadiologi_2" true-value="Extremitas" label="Extremitas" class="p-0"
                  color="primary" squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketExtremitas" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.hasilRadiologi_3" true-value="CT Scane" label="CT Scane" class="p-0"
                  color="primary" squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketCTScane" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.hasilRadiologi_4" true-value="MRI" label="MRI" class="p-0" color="primary"
                  squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketMRI" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <h1 class="title-akt">B. CEKLIST PERSIAPAN OPERASI (Diisi oleh perawat ruangan dan perawat kamar operasi)</h1>
      <div class="columns is-multiline pt-4 pr-3 pl-3">
        <div class="column is-4" v-for="(persiapans) in persiapanOperasi">
          <span class="label-akt">{{ persiapans.title }}</span>
          <div class="column pb-0 mb-3" v-for="(datas) in persiapans.details">
            <span class="label-akt">{{ datas.subTitle }}</span>
            <div class="column pb-0 ml-2 mr-2" v-for="(data) in datas.detail">
              <span class="label-akt">{{ data.judul }}</span>
              <div class="columns is-multiline pl-3 pt-3 pr-3">
                <div class="column is-4" v-for="(pilihan) in data.value">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label"
                        class="p-0" color="primary" squere />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-8 pl-4 pr-4">
              <span class="label-akt">{{ datas.optional.label }}</span>
              <VField class="pt-3">
                <VControl>
                  <VInput type="text" v-model="input[datas.optional.model]" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column is-7">
          <div class="column" v-for="(persiapans) in persiapanOperasiII">
            <span class="label-akt">{{ persiapans.title }}</span>
            <div class="columns is-multiline">
              <div class="column is-6 pb-0 mb-3 " v-for="(datas) in persiapans.details">
                <span class="label-akt">{{ datas.subTitle }}</span>
                <div class="column pb-0 ml-2 mr-2" v-for="(data) in datas.detail">
                  <span class="label-akt">{{ data.judul }}</span>
                  <div class="columns is-multiline pl-3 pt-3 pr-3">
                    <div class="column is-4" v-for="(pilihan) in data.value">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label"
                            class="p-0" color="primary" squere />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                </div>
                <div class="column is-8 pl-4 pr-4">
                  <span class="label-akt">{{ datas.optional.label }}</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input[datas.optional.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="column">
            <span class="label-akt">III. PERSIAPAN LAIN-LAIN</span>
            <div class="column is-5">
              <span class="label-akt">Site Marketing</span>
              <div class="columns is-multiline p-3">
                <div class="column is-5">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.siteMarketing" true-value="Ya" label="Ya" class="p-0" color="primary"
                        squere />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-5">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input.siteMarketing" true-value="Tidak" label="Tidak" class="p-0"
                        color="primary" squere />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <h1 class="title-akt">C. PENGKAJIAN INTRA OPERASI (Diisi lengkap 01eh staff kamar operasi)</h1>
      <div class="column" v-for="(datas) in pengkajianIntra">
        <span class="label-akt">{{ datas.title }}</span>
        <div class="column" v-for="(data) in datas.detail" v-if="datas.detail">
          <span class="label-akt" v-if="data.subTitle">{{ data.subTitle }}</span>
          <div class="columns is-multiline pl-3 pr-3" :class="data.subTitle ? 'pt-3' : ''">
            <div class="column is-2 pb-0" v-for="(pilihan) in data.value">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label" class="p-0"
                    color="primary" squere />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-5 pb-0" v-if="data.optional">
            <VField>
              <VControl>
                <VInput type="text" v-model="input[data.optional.model]" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column">
        <span class="label-akt">9. Memakai kateter urine</span>
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.diRuangan" true-value="Ya" label="Diruangan" class="p-0" color="primary"
                  squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketRuangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.dalamKamarOP" true-value="Ya" label="Dalam kamar operasi" class="p-0"
                  color="primary" squere />
              </VControl>
            </VField>
            <VField>
              <VControl>
                <VInput type="text" v-model="input.ketDalamKamarOP" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.memakaiKateter" true-value="Tidak" label="Tidak" class="p-0" color="primary"
                  squere />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="column">
        <span class="label-akt">10. Pemakaian Tourniquet</span>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-3">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.pmkTourniquet" true-value="Ya" label="Ya" class="p-0" color="primary" squere />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input.pmkTourniquet" true-value="Ya" label="Ya" class="p-0" color="primary" squere />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="label-akt">Tempat</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input.tempatTour" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3">
            <span class="label-akt">Jam Mulai</span>
            <VDatePicker v-model="item.jamMulai" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-3">
            <span class="label-akt">Jam selesai</span>
            <VDatePicker v-model="item.jamSelesai" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
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
        <span class="label-akt">11. Hitung Kasa/Depper/Kassa Besar Jarum Instrumen</span>
        <div class="column pb-2">
          <span class="label-akt">Hitungan 1(sebelum insisi)</span>
          <div class="columns is-multiline p-3">
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.kasa" true-value="Benar" label="Benar,Jumlah" class="p-0" color="primary"
                    squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jumlahKasa" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.depper" true-value="Benar" label="Benar,Jumlah" class="p-0" color="primary"
                    squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jumlahDepper" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jarum" true-value="Benar" label="Benar,Jumlah" class="p-0" color="primary"
                    squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jumlahJarum" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column pt-0">
          <span class="label-akt">Hitungan 2(sebelum tutup peritonim/kulit)</span>
          <div class="columns is-multiline p-3">
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.kasaPeritonim" true-value="Benar" label="Benar,Jumlah" class="p-0"
                    color="primary" squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jmlKasaPeritonim" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.depperPeritonim" true-value="Benar" label="Benar,Jumlah" class="p-0"
                    color="primary" squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jmlDepperPeritonim" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.jarumPeritonim" true-value="Benar" label="Benar,Jumlah" class="p-0"
                    color="primary" squere />
                </VControl>
              </VField>
              <VField>
                <VControl>
                  <VInput type="text" v-model="input.jmlJarumPeritonim" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column" v-for="(datas) in pemakian">
        <span class="label-akt">{{ datas.title }}</span>
        <div class="columns is-multiline pt-3 pl-4 pr-4">
          <div class="column is-2" v-for="(data) in datas.value">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                  color="primary" squere />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-5 pt-0" v-if="datas.optional">
          <span class="label-akt">{{ datas.optional.label }}</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input[datas.optional.model]" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column">
        <h1 class="title-akt">D. CATATAN KEPERAWATAN Dl RUANGAN PULIH SADAR ( Diisi o1eh perawat ruang pulih sadar )</h1>
        <div class="column">
          <span class="label-akt">PENGKAJIAN SESUDAH OPERASI</span>
          <div class="column">
            <span class="label-akt">Ruang pulih sadar</span>
            <div class="columns is-multiline pl-3 pt-3 pr-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kasa" true-value="Benar" label="Ya, Masuk Jam" class="p-0" color="primary"
                      squere />
                  </VControl>
                </VField>
                <VDatePicker v-model="input.jamMasukOP" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl>
                        <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column is-2">
                <span class="label-akt">Keluar Jam</span>
                <VDatePicker v-model="input.jamKeluarOP" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="pt-3">
                      <VControl>
                        <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
            </div>
          </div>
          <div class="column" v-for="(datas) in pengkajianSesudahOP">
            <span class="label-akt">{{ datas.title }}</span>
            <div class="columns is-multiline pl-3 pr-3 pt-3">
              <div class="column pb-0" v-for="(data) in datas.value"
                :class="data.value == 'Jelek' || data.value == 'LN' ? 'is-2' : 'is-3'">
                <VField v-if="data.type == 'checkBox'">
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" class="p-0"
                      color="primary" squere />
                  </VControl>
                </VField>
                <VField v-else>
                  <VControl>
                    <VInput type="text" v-model="input[data.model]" :placeholder="data.label" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column" v-for="(datas) in kulit">
            <span class="label-akt">{{ datas.title }}</span>
            <div class="column" v-for="(data) in datas.detail">
              <span class="label-akt">{{ data.subTitle }}</span>
              <div class="columns is-multiline pl-3 pr-3 pt-3">
                <div class="column" v-for="(value) in data.value" :class="value.code == 'LN' ? 'is-2' : 'is-3'">
                  <VField v-if="value.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[value.model]" :true-value="value.code" :label="value.label" class="p-0" style="vertical-align: inherit;"
                        color="primary" squere />
                    </VControl>
                  </VField>
                  <VField v-else class="p-0">
                    <VControl>
                      <VInput type="text" v-model="input[value.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="columns is-multiline">
            <div class="column is-3">
              <span class="label-akt">Tanggal & Jam</span>
              <VDatePicker v-model="item.tglDanJam" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField class="pb-0 pt-3">
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
            </div>
            <div class="column is-4">
              <span class="label-akt">Perawat Ruangan</span>
              <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatRuangan" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Dokter..." />
              </VControl>
            </VField>
            </div>
            <div class="column is-4">
              <span class="label-akt">Perawat kamar operasi</span>
              <VField class="pt-3">
              <VControl class="prime-auto">
                <AutoComplete v-model="input.perawatKamarOperasi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                  :field="'label'" placeholder="Cari Dokter..." />
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onBeforeMount } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-tindakan-pre-operatif'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let pengkajianSebelum = (EMR.pengkajianSebelumOperasi())
let persiapanOperasi = (EMR.persiapanOperasi())
let persiapanOperasiII = (EMR.persiapanOperasiII())
let pengkajianIntra = (EMR.pengkajianIntra())
let pemakian = (EMR.pemakian())
let pengkajianSesudahOP = (EMR.pengkajianSesudahOP())
let kulit = (EMR.kulit())

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

const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const loadData: any = ref(true)
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
  jamMasukOP: new Date(),
  jamKeluarOP: new Date()
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
  let response = await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
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


const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    ).then((response) => {

        input.value.suhu = response ? response.suhu : ''
        input.value.nadi = response ? response.nadi : ''
        input.value.tekananDarah = response ? response.tekananDarah : ''
        input.value.pernapasan = response ? response.pernapasan : ''
    })
}
setView()
setAutoFill()


onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${route.name}`)
    if (cache) input.value = cache
    loadData.value = false
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error);
  }
});

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = from?.name
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error);
  }
  next();
});

</script>

<style lang="scss">

.label-akt{
  font-weight: 500;
}

.title-akt{
  font-weight: bold;
}

</style>
